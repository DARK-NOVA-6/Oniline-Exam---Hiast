
<div class="mainContainer column" id="mainContainer">
    <form action="">
    <div id="marginContainer">

    </div>
    <div class="row buttonContainer" >
        <input type="submit" id="submitButton" hidden>
        <button id='save' class='button profileEditButton'>Save</button>
        <button id='retrive' class='button profileEditButton'>Retrive</button>
    </div>
    </form>
</div>
<script src="<?php echo URL; ?>public/js/textInputChecker.js"></script>


<script>
    var itemsArr = [];
    var index = 0;
    formFields = {
        EMAIL: [],
        NAME: [],
        PHONE: [],
        PASSWORD: [],
    };
    <?php foreach ($profileArr as $pa){
            ?> items = []; <?php
            foreach ($pa as $pi) {
                if($pi->basicType=='TextInput'){ ?>
                    items.push(
                        new TextInput(
                            '<?php echo $pi->name ?>',
                            '<?php echo $pi->type ?>'
                        )
                    ); <?php 
                    if($pi->type=='PASSWORD'){ ?>
                        items.push(
                            new ProgPar()
                        ); <?php
                    } 
                }
                else if($pi->basicType=='Photo'){ ?>
                    items.push( 
                        new Photo(
                            '<?php echo $pi->name ?>',
                            '<?php echo $pi->type ?>'
                        )
                    ); <?php
                }  
            } ?>
            itemsArr[index] = items;
            index++; <?php
    } ?>
    buildProfile(itemsArr);
    
    initializeFormValidator('save', 'submitButton', formFields);
    

    $('#retrive')
        .on('click',
            () => {
                location.reload();
         }
        );
</script>
<link rel="stylesheet" href="<?php echo URL; ?>public/css/textInput.css" />
