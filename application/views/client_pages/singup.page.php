<div class="container" id="accTypeChosr">
    <div class="cardContainer">
        <div class="card cardAnimate" id="adminCard" onclick="selectCard('adminCard')">
            <div class="cardImg">
                <img src="<?php echo URL; ?>public/icon/accTypAdmin.svg"/>
            </div>

            <div class="cardOverlay">
                <div class="cardTitle">
                    <div class="cardTitleArc2"></div>
                    <div class="cardTitleArc1"></div>
                    Admin
                </div>

                <div class="cardDescription">
                    The maneger of the university
                </div>
            </div>
        </div>

        <div class="card cardAnimate" id="tcAdminCard" onclick="selectCard('tcAdminCard')">
            <div class="cardImg">
                <img src="<?php echo URL; ?>public/icon/accTypTestCntrAdmin.svg"/>
            </div>

            <div class="cardOverlay">
                <div class="cardTitle">
                    <div class="cardTitleArc2"></div>
                    <div class="cardTitleArc1"></div>
                    Test Center Admin
                </div>

                <div class="cardDescription">
                    Who requests exam questions for his center
                </div>
            </div>
        </div>

        <div class="card cardAnimate" id="studentCard" onclick="selectCard('studentCard')">
            <div class="cardImg">
                <img src="<?php echo URL; ?>public/icon/accTypStudent.svg"/>
            </div>

            <div class="cardOverlay">
                <div class="cardTitle">
                    <div class="cardTitleArc2"></div>
                    <div class="cardTitleArc1"></div>
                    Student
                </div>

                <div class="cardDescription">
                    A university student will take exams
                </div>
            </div>
        </div>
    </div>

    <div class="button" id="selectButton" onclick="getForm()">
        Select
    </div>
</div>

<!--











		-->

<!--








		-->

<div class="container" id="signupForm" style="display: none;">
    <!-- <div class="container" id="singupForm"> -->
    <div id="formHeader">
        <div class="button" id="backButton" onclick="getAccTypeChoser()" title="Back">
            <img src="<?php echo URL; ?>public/icon/left_arrow1.png" alt="Back">
        </div>
        <span>Sign-Up</span>
    </div>
    <form autocomplete="off" action="<?php echo URL; ?>user/add_user" method="POST" enctype="multipart/form-data">

        <div class="row">

            <div class="column">
                <div class="textInput" id="fnameInput">
                    <input name="fname" type="text" id="fname" value="">
                    <label for="fname">First Name</label>
                    <div style="position:relative;">
                        <img src="<?php echo URL; ?>public/icon/hint.svg" alt="Ok" id="fnameImg">
                        <div class="tips" id="fnameTips">
                            enter your first name
                        </div>
                    </div>
                </div>

                <div class="textInput" id="lnameInput">
                    <input name="lname" type="text" id="lname" value="">
                    <label for="lname">Last Name</label>
                    <div style="position:relative;">
                        <img src="<?php echo URL; ?>public/icon/hint.svg" alt="Ok" id="lnameImg">
                        <div class="tips" id="lnameTips">
                            enter your last name
                        </div>
                    </div>
                </div>

                <div class="textInput" id="emailInput">
                    <input name="email" type="text" id="email" value="">
                    <label for="email">Email <span>@hiast.edu.sy</span> </label>
                    <div style="position:relative;">
                        <img src="<?php echo URL; ?>public/icon/hint.svg" alt="Ok" id="emailImg">
                        <div class="tips" id="emailTips">
                            enter your email
                        </div>
                    </div>
                </div>


                <div class="textInput" id="phoneInput">
                    <input name="phone" type="text" id="phone" value="">
                    <label for="phone">Phone <i><small>(recomended)</small> </i> </label>
                    <div style="position:relative;">
                        <img src="<?php echo URL; ?>public/icon/hint.svg" alt="Ok" id="phoneImg">
                        <div class="tips" id="phoneTips">
                            enter your phone
                        </div>
                    </div>
                </div>


                <div class="textInput" id="passwordInput">
                    <input name="password" type="password" id="password" autocomplete="new-password" value="">
                    <label for="password">Password</label>
                    <div style="position:relative;">
                        <img src="<?php echo URL; ?>public/icon/hint.svg" alt="Ok" id="passwordImg">
                        <div class="tips" id="passwordTips">
                            enter your password
                        </div>
                    </div>
                </div>


                <div id="prog">

                    <div role="progressbar" aria-valuenow="40" aria-valuemax="100" aria-valuemin="0"
                         id="passwordProgBar">

                    </div>

                </div>


                <div class="textInput" id="confPasswordInput">
                    <input name="confPassword" type="password" id="confPassword" value="">
                    <label for="confPassword">Confirm Password</label>
                    <div style="position:relative;">
                        <img src="<?php echo URL; ?>public/icon/hint.svg" alt="Ok" id="confPasswordImg">
                        <div class="tips" id="confPasswordTips">
                            enter the same password
                        </div>
                    </div>
                </div>

                <input name="role_title" type="hidden" id="roleAccount">

                <div id="tcContainer">
                    <div class="textInput" id="testCenterInput">
                        <input name="testCenter" type="text" id="testCenter" value="">
                        <label for="testCenter"> Test Center </label>
                        <div style="position:relative;">
                            <img src="<?php echo URL; ?>public/icon/hint.svg" alt="Ok" id="testCenterImg">
                            <div class="tips" id="testCenterTips">
                                enter the test center name
                            </div>
                        </div>
                    </div>
                    <div class="autocomplete">
                    </div>
                </div>
            </div>


            <div id="photoController" class='photoController'>

                <div id="photoButtons">
                    <label for="photoSelect" class="button photoButton" id="photoSelectButton">Select Photo</label>
                    <input name="photo" type="file" id="photoSelect" accept="image/png, image/jpeg"
                           onchange="updateImg('profilePhoto','photoSelect')" style="width: 0;">
                    <div class="button photoButton" id="deletePhotoButton" onclick="deleteImg('profilePhoto')">Delete
                    </div>
                </div>


                <div id="profilePhoto" class='profilePhoto'>
                    <img src="<?php echo URL; ?>public/icon/person.svg" alt="profile photo">
                </div>
                <div id="accountTypeContainer">
                    <span id="accountType"></span>
                </div>


            </div>


        </div>
        <hr>
        <input type="submit" id="submitButton" hidden>
        <div for="submitButton" class="button" id="submitButtonStyle">Create Account</div>
    </form>

    <script>
    </script>

</div>


<script src="<?php echo URL; ?>public/js/textInputAutoComplete.js"></script>
<script src="<?php echo URL; ?>public/js/imageSelector.js"></script>
<script src="<?php echo URL; ?>public/js/signupAnimation.js"></script>
<script src="<?php echo URL; ?>public/js/textInputChecker.js"></script>

<script>
    var testCenter = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Anguilla", "Antigua &amp; Barbuda",
        "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh",
        "Barbados"];
    initializeAutocomplete(document.getElementById('testCenterInput'), testCenter, 5);
</script>

<script>
    // initializeInputValidator('fname', 'NAME');
    // initializeInputValidator('lname', 'NAME');
    formFields = {
        EMAIL: [
            {
                id: 'email',
                require: true,
            }
        ],
        NAME: [
            {
                id: 'fname',
                require: true,
            },
            {
                id: 'lname',
                require: true
            }
        ],
        PHONE: [
            {
                id: 'phone',
                require: false,
            }   
        ],
        PASSWORD: [
            {
                passwordId: 'password',
                confPasswordId: 'confPassword',
            }
        ],
    };
    initializeFormValidator('submitButtonStyle', 'submitButton', formFields);

    initializeInteractiveChecker('email', 'EMAIL', 'AVAILABLE', 'NOT_AVAILABLE',
        s => s + '@hiast.edu.sy', LocalURL + 'auto.php', 'emailAvailability');
</script>