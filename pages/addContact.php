<?php

/* HERE I REQUIRE AND USE THE STICKYFORM CLASS THAT DOES ALL THE VALIDATION AND CREATES THE STICKY FORM.  THE STICKY FORM CLASS USES THE VALIDATION CLASS TO DO THE VALIDATION WORK.*/
require_once('classes/StickyForm.php');
$path = "index.php?page=login";
$stickyForm = new StickyForm();

/*THE INIT FUNCTION IS WRITTEN TO START EVERYTHING OFF IT IS CALLED FROM THE INDEX.PHP PAGE */
function init()
{
    global $elementsArr, $stickyForm, $path;

    if ($stickyForm->checkLogin()) {

        /* IF THE FORM WAS SUBMITTED DO THE FOLLOWING  */
        if (isset($_POST['submit'])) {

            /*THIS METHODS TAKE THE POST ARRAY AND THE ELEMENTS ARRAY (SEE BELOW) AND PASSES THEM TO THE VALIDATION FORM METHOD OF THE STICKY FORM CLASS.  IT UPDATES THE ELEMENTS ARRAY AND RETURNS IT, THIS IS STORED IN THE $postArr VARIABLE */
            $postArr = $stickyForm->validateForm($_POST, $elementsArr);

            /* THE ELEMENTS ARRAY HAS A MASTER STATUS AREA. IF THERE ARE ANY ERRORS FOUND THE STATUS IS CHANGED TO "ERRORS" FROM THE DEFAULT OF "NOERRORS".  DEPENDING ON WHAT IS RETURNED DEPENDS ON WHAT HAPPENS NEXT.  IN THIS CASE THE RETURN MESSAGE HAS "NO ERRORS" SO WE HAVE NO PROBLEMS WITH OUR VALIDATION AND WE CAN SUBMIT THE FORM */
            if ($postArr['masterStatus']['status'] == "noerrors") {

                /*addData() IS THE METHOD TO CALL TO ADD THE FORM INFORMATION TO THE DATABASE (NOT WRITTEN IN THIS EXAMPLE) THEN WE CALL THE GETFORM METHOD WHICH RETURNS AND ACKNOWLEDGEMENT AND THE ORGINAL ARRAY (NOT MODIFIED). THE ACKNOWLEDGEMENT IS THE FIRST PARAMETER THE ELEMENTS ARRAY IS THE ELEMENTS ARRAY WE CREATE (AGAIN SEE BELOW) */
                return addData($_POST);

            } else {
                /* IF THERE WAS A PROBLEM WITH THE FORM VALIDATION THEN THE MODIFIED ARRAY ($postArr) WILL BE SENT AS THE SECOND PARAMETER.  THIS MODIFIED ARRAY IS THE SAME AS THE ELEMENTS ARRAY BUT ERROR MESSAGES AND VALUES HAVE BEEN ADDED TO DISPLAY ERRORS AND MAKE IT STICKY */
                return getForm("", $postArr);
            }

        } /* THIS CREATES THE FORM BASED ON THE ORIGINAL ARRAY THIS IS CALLED WHEN THE PAGE FIRST LOADS BEFORE A FORM HAS BEEN SUBMITTED */
        else {
            return getForm("", $elementsArr);
        }

    }

    header('location: ' . $path);
}

/* THIS IS THE DATA OF THE FORM.  IT IS A MULTI-DIMENTIONAL ASSOCIATIVE ARRAY THAT IS USED TO CONTAIN FORM DATA AND ERROR MESSAGES.   EACH SUB ARRAY IS NAMED BASED UPON WHAT FORM FIELD IT IS ATTACHED TO. FOR EXAMPLE, "NAME" GOES TO THE TEXT FIELDS WITH THE NAME ATTRIBUTE THAT HAS THE VALUE OF "NAME". NOTICE THE TYPE IS "TEXT" FOR TEXT FIELD.  DEPENDING ON WHAT HAPPENS THIS ASSOCIATE ARRAY IS UPDATED.*/
$elementsArr = [
    "masterStatus" => [
        "status" => "noerrors",
        "type" => "masterStatus"
    ],
    "name" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Name cannot be blank and must be a standard name</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "Scott",
        "regex" => "name"
    ],
    "address" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Address cannot be blank and must be a valid adress</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "4384400",
        "regex" => "address"
    ],
    "city" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>City cannot be blank and must be a valid city</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "Anywhere",
        "regex" => "city"
    ],
    "email" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Email cannot be blank and must be a valid email</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "staff@admin.com",
        "regex" => "email"
    ],
    "date_of_birth" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Date of Birth cannot be blank and must be a valid date in the format 12/25/2002</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "12/25/1999",
        "regex" => "date_of_birth"
    ],
    "phone" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>Phone cannot be blank and must be a valid phone number</span>",
        "errorOutput" => "",
        "type" => "text",
        "value" => "999.999.9999",
        "regex" => "phone"
    ],
    "state" => [
        "type" => "select",
        "options" => ["mi" => "Michigan", "oh" => "Ohio", "pa" => "Pennslyvania", "tx" => "Texas"],
        "selected" => "oh",
        "regex" => "state"
    ],
    "contact_type" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>You must select at least one Contact Type</span>",
        "errorOutput" => "",
        "type" => "checkbox",
        "action" => "notRequired",
        "status" => ["newsletter" => "", "email" => "", "text" => ""]
    ],
    "age_range" => [
        "errorMessage" => "<span style='color: red; margin-left: 15px;'>You must select at set an Age</span>",
        "errorOutput" => "",
        "action" => "required",
        "type" => "radio",
        "value" => ["10-18" => "", "19-30" => "", "30-50" => "", "51+" => ""]
    ]
];


/*THIS FUNCTION CAN BE CALLED TO ADD DATA TO THE DATABASE */
function addData($post)
{
    global $elementsArr;
    /* IF EVERYTHING WORKS ADD THE DATA HERE TO THE DATABASE HERE USING THE $_POST SUPER GLOBAL ARRAY */
    //print_r($_POST);
    require_once('classes/Pdo_methods.php');

    $pdo = new PdoMethods();

    $sql = "INSERT INTO contacts (name, phone, state,city,email,address,date_of_birth, contact_type, age_range) VALUES (:name, :phone, :state,:city,:email,:address,:date_of_birth,:contact_type,:age_range)";

    /* THIS TAKE THE ARRAY OF CHECK BOXES AND PUT THE VALUES INTO A STRING SEPERATED BY COMMAS  */
    if (isset($_POST['contact_type'])) {
        $contact_type = "";
        foreach ($post['contact_type'] as $v) {
            $contact_type .= $v . ",";
        }
        /* REMOVE THE LAST COMMA FROM THE CONTACTS */
        $contact_type = substr($contact_type, 0, -1);
    } else {
        $contact_type = "";
    }


    $bindings = [
        [':name', $post['name'], 'str'],
        [':phone', $post['phone'], 'str'],
        [':state', $post['state'], 'str'],
        [':city', $post['city'], 'str'],
        [':email', $post['email'], 'str'],
        [':address', $post['address'], 'str'],
        [':date_of_birth', $post['date_of_birth'], 'str'],
        [':contact_type', $contact_type, 'str'],
        [':age_range', $post['age_range'], 'str']
    ];

    $result = $pdo->otherBinded($sql, $bindings);

    if ($result == "error") {
        return getForm("<p>There was a problem processing your form</p>", $elementsArr);
    } else if ($result == "duplicate") {
        return getForm("<p>User With This Email Already Exists</p>", $elementsArr);
    } else {
        return getForm("<p>Contact Information Added</p>", $elementsArr);
    }

}


/*THIS IS THEGET FROM FUCTION WHICH WILL BUILD THE FORM BASED UPON UPON THE (UNMODIFIED OF MODIFIED) ELEMENTS ARRAY. */
function getForm($acknowledgement, $elementsArr)
{

    global $stickyForm;
    $options = $stickyForm->createOptions($elementsArr['state']);

    /* THIS IS A HEREDOC STRING WHICH CREATES THE FORM AND ADD THE APPROPRIATE VALUES AND ERROR MESSAGES */
    $form = <<<HTML
    <form method="post" action="index.php?page=addContact">
    <div class="form-group">
      <label for="name">Name (letters only){$elementsArr['name']['errorOutput']}</label>
      <input type="text" class="form-control" id="name" name="name" value="{$elementsArr['name']['value']}" >
    </div>
    <div class="form-group">
      <label for="address">Address (number and letters){$elementsArr['address']['errorOutput']}</label>
      <input type="text" class="form-control" id="address" name="address" value="{$elementsArr['address']['value']}" >
    </div>
    <div class="form-group">
      <label for="city">City (letters only){$elementsArr['city']['errorOutput']}</label>
      <input type="text" class="form-control" id="city" name="city" value="{$elementsArr['city']['value']}" >
    </div>
    <div class="form-group">
      <label for="state">State</label>
      <select class="form-control" id="state" name="state">
        $options
      </select>
    </div>
    <div class="form-group">
      <label for="phone">Phone (format 999.999.9999) {$elementsArr['phone']['errorOutput']}</label>
      <input type="text" class="form-control" id="phone" name="phone" value="{$elementsArr['phone']['value']}" >
    </div>
     <div class="form-group">
      <label for="email">email {$elementsArr['email']['errorOutput']}</label>
      <input type="text" class="form-control" id="email" name="email" value="{$elementsArr['email']['value']}" >
    </div> 
     <div class="form-group">
      <label for="date_of_birth">date {$elementsArr['date_of_birth']['errorOutput']}</label>
      <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" value="{$elementsArr['date_of_birth']['value']}" >
    </div>      
    <p>Please check all Contact types you would like (optional):{$elementsArr['contact_type']['errorOutput']}</p>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="contact_type[]" id="contact_type1" value="newsletter" {$elementsArr['contact_type']['status']['newsletter']}>
      <label class="form-check-label" for="contact_type1">NewsLetter</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="contact_type[]" id="contact_type2" value="email" {$elementsArr['contact_type']['status']['email']}>
      <label class="form-check-label" for="contact_type2">Email Updates</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="contact_type[]" id="contact_type3" value="text" {$elementsArr['contact_type']['status']['text']}>
      <label class="form-check-label" for="contact_type3">Text Updates</label>
    </div>
        

    <p>Please select age range (you must select one):{$elementsArr['age_range']['errorOutput']}</p>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="age_range" id="age_range1" value="10-18"  {$elementsArr['age_range']['value']['10-18']}>
      <label class="form-check-label" for="age_range1">10-18</label>
    </div>

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="age_range" id="age_range2" value="19-30"  {$elementsArr['age_range']['value']['19-30']}>
      <label class="form-check-label" for="age_range2">19-30</label>
    </div>

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="age_range" id="age_range3" value="30-50"  {$elementsArr['age_range']['value']['30-50']}>
      <label class="form-check-label" for="age_range3">30-50</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="age_range" id="age_range4" value="51+"  {$elementsArr['age_range']['value']['51+']}>
      <label class="form-check-label" for="age_range4">51+</label>
    </div>

    <div style="margin-top:10px;">
    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>

HTML;

    /* HERE I RETURN AN ARRAY THAT CONTAINS AN ACKNOWLEDGEMENT AND THE FORM.  THIS IS DISPLAYED ON THE INDEX PAGE. */
    return [$acknowledgement, $form];

}

?>