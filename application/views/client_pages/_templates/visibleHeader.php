
<body class="body" id="headBody">
    <header class="header" id="header">
        <a id="logo" href="http://hiast.edu.sy" target="_blank">
            <img src="<?php echo URL;?>public/icon/HIAST logo.png" alt="HIAST logo" id="logoimg" /> HIAST
        </a>
        <div id="optionContainer">
            <div id="logInOutContainer">
                <a id='profileLink' href='<?php echo URL.'user/profile';?>' 
                    style='<?php 
                        if($roleTitle == 'test_center_admin'&& $_GET['url']=='user/login'){
                            echo "pointer-events: none;";
                        } else {
                        } ?>'
                    >
                    <img id='accountPhoto' src='<?php echo $photoLink; ?>'>
                    <div id="accountDetails"></div>
                </a>
                <div id="logInOut"></div>
                <script>
                    var lIn = "<?php 
                        $userName = $userName??'';
                        if($roleTitle == 'test_center_admin'&& $_GET['url']=='user/login'){
                            echo $testCenterName;
                        } else { 
                            echo $userName;
                        }
                     ?>";

                    $('#logInOut')
                        .on('click', () => 
                            window.location.href =
                                LocalURL + 'user/' + 
                                (lIn == '' ? 'login' : 'logout')
                        );
                </script>
            </div>
            <div id="languageSelector">
                <div class="button lSelect lSelected" id="EnLanguage" onclick="switchLang('En')">
                    English
                </div>
                <div class="button lSelect" id="ArLanguage" onclick="switchLang('Ar')">
                    العربية
                </div>
            </div>
        </div>
    </header>
    <div class="backgroundSection">
      <div id="screenDisable1" style="display: none;">
        <div id="screenDisable2">
          <div class="loadingImg" id="boxScalingLoadin">
            <div class="loadingImg" id="boxRotatingLoadin">
              <img src="" alt="" id="whiteShape1">
              <img src="" alt="" id="whiteShape2">
              <img src="" alt="" id="blueShape">
            </div>
          </div>
        </div>
      </div>