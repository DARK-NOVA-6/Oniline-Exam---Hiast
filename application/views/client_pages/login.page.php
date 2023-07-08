<!--








			  -->

<div class="container" id="loginForm">
    <div id="formHeader">
        <div id='title'>
            <span>Log-In </span>
			<?php
			if ($roleTitle == 'test_center_admin')
				echo '<i>Student & TestCenter only</i>'
			?>
        </div>
    </div>

    <form autocomplete="off" action="<?php echo URL; ?>user/login_process" method="POST">

        <div class="column">

            <div class="textInput" id="emailInput">
                <input name="email" type="text" id="email" value=""
                       oninput='$("#email").attr("value", document.getElementById("email").value);'>
                <label for="email">Email <span>@hiast.edu.sy</span> </label>
                <div class="tips" id="emailTips">
                    enter your email
                </div>
            </div>


            <div class="textInput" id="passwordInput">
                <input name="password" type="password" id="password" autocomplete="new-password" value=""
                       oninput='$("#password").attr("value", document.getElementById("password").value);'>
                <label for="password">Password</label>
                <div class="tips" id="passwordTips">
                    enter your password
                </div>
            </div>


            <div id="invalidLogin" style="display: none;">
                <img src="icons/warning.svg" alt=""> Invalid login, please try again
            </div>
            <div id="getHelp">
                Forgot password? <a href="">contact-us</a>
            </div>
        </div>


        <hr>
        <input type="submit" id="submitButton" style="width: 0; padding: 0; border: 0;">
        <label for="submitButton" class="button" id="submitButtonStyle">Login</label>
    </form>

</div>

