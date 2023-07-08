<script> 
    duration=1;
    questions = [{}];
    currQues = 1;
    <?php foreach ($questions as $q) { ?>
        options = [{}];
        
        <?php foreach ($q->options as $o) { ?>
            options.push({
                text : '<?php echo $o->text; ?>',
                order : <?php echo $o->order; ?>,
                isSelected : <?php echo ($o->isSelected)? 'true' : 'false'; ?>
            });
        <?php } ?>
        order = '<?php echo $q->order; ?>';
        questions[order] = {
            title : '<?php echo $q->title; ?>',
            text : '<?php echo $q->text; ?>',
            id: '<?php echo $q->id; ?>',
            options : options
        };
    <?php } ?>
    totques = questions.length - 1;
    startDate =1;
    examFinished = false;
    testing= true;
    correctAnswer = [{}];
    <?php if(isset($answers)){ 
        foreach ($answers as $a) { 
            ?>  correctAnswer.push({
                    questionId  : '<?php echo $a->questionId; ?>',
                    choices     : '<?php echo $a->choices; ?>',
                    correctAn   : '<?php echo $a->answersAsString; ?>',
                    mark        : '<?php echo $a->mark; ?>',
                    fMark       : '<?php echo $a->fMark; ?>'
            });  <?php
        } 
    } ?>
</script>

<aside id="infoBar">
    <!-- <div id="timerCapsul">
        <label for="timerBar">
                running time:
        </label>
        <div class="timer" id="timerBar"></div>
    </div> -->

    <div class="capsul">
        <label for="duration">
            Duration:
        </label>
        <input 
            type='number'
            class="testInfo"
            id="duration"
            min=1
            require
            placeholder='in minutes'
            autofocus
            ></input>
    </div>

    <div class="capsul">
        <label for="startDate" id='durL'>Start Date:</label>
        <input type="text" class="testInfo" id="startDate" readonly></input>
    </div>


    <div class="capsul">
        <label for="addTc">
            Test Centers:
        </label>
        <div  class="button" id="addTc" >Add Test Center</div>
    </div>
    <div id='tcList'> 
    </div> 

</aside>
<aside id="submitBar">
    <!-- <div id="submitBSection">
        <button id='submitB' class='button' onclick='askToEnd()'>Submit</button>
    </div> -->
</aside>



<div id="quesContainer">
    <div id='quesTitleAndMark'>
        <div> 
            Question <b id="quesNumb"></b>?
        </div>
        <div id='quesMark'></div>
    </div>
    
    <div id="textQuesSec">
        <p></p>
        <hr />
    </div>

    <div id="optionsSec"></div>

    <hr style="width: 100%; height: 5px; background-color: black;" />
    <div id="pageMovCtrl">
        <div class="itemCtrl" id="numberCtrl">
            <div class="movTo" id="first">
                <img src="<?php echo URL; ?>public/icon/left_arrow2.png" alt="1" onclick="changeQues(1)" />
            </div>
            <div class="movTo" id="prev">
                <img src="<?php echo URL; ?>public/icon/left_arrow1.png" alt="P" onclick="changeQues(currQues - 1)" />
            </div>
            <div class="movTo" id="list">
                ...
            </div>
            <div class="movTo" id="next">
                <img src="<?php echo URL; ?>public/icon/right_arrow1.png" alt="N" onclick="changeQues(currQues + 1)" />
            </div>
            <div class="movTo" id="last">
                <img src="<?php echo URL; ?>public/icon/right_arrow2.png" alt="L" onclick="changeQues(totques)" />
            </div>
        </div>
    </div>
</div>

<script>
    // startTimer();
    buildList();
    buildQuestion();
    buildDate();
</script>