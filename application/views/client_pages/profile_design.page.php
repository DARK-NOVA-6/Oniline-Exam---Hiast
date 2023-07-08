
<div class="mainContainer column" id="mainContainer">
    <div class="row buttonContainer" >
        <button id='save' class='button profileEditButton'>Save</button>
        <button id='retrive' class='button profileEditButton'>Retrive</button>
    </div>
    <div id="marginContainer">

    </div>
</div>
<script>
    var itemsArr = [];
    var index = 0;
    <?php foreach ($profileArr as $pa){
            ?> items = []; <?php
            foreach ($pa as $pi) {
                if($pi->basicType=='TextInput'){ ?>
                    items.push(
                        new TextInput('<?php echo $pi->name ?>')
                    ); <?php
                }
                else if($pi->basicType=='Photo'){ ?>
                    items.push( 
                        new Photo('<?php echo $pi->name ?>')
                    ); <?php
                }  
            } ?>
            itemsArr[index] = items;
            index++; <?php
    } ?>
    buildSortable(itemsArr);
</script>

<script src="<?php echo URL; ?>public/js/actionSortableProfile.js"></script>
