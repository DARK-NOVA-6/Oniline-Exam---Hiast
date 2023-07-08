<script> 
    duration = '<?php echo $duration ?>';
    questions = [{}];
    fullMark = '<?php echo $fullMark ?>';
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
    nSolved = 0;
    for (q in questions) {
        if(q==0){
            continue;
        }
        solved = false;
        for(o in questions[q].options) {
            solved |= questions[q].options[o].isSelected;
        }
        if(solved){
            nSolved++;
        }
    }
    currQues = <?php echo $curQuestions; ?>;
    totques = questions.length - 1;
    nQuesLeft = totques- nSolved;
    startDate = '<?php echo $startDate;?>';
    examFinished = <?php echo ($examFinished)?'true':'false';?>;
    fTime = '<?php echo $fTime;?>';
    correctAnswer = [{}];
    testing = false;
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
    <div id="timerCapsul">
        <label for="timerBar">
                running time:
        </label>
        <div class="timer" id="timerBar"></div>
    </div>

    <div class="capsul">
        <label for="totques">
            Total number of questions:
        </label>
        <div class="testInfo" id="totques"></div>
    </div>

    <div class="capsul">
        <label for="nQuesLeft">
                Questions left to answer:
        </label>
        <div class="testInfo" id="nQuesLeft"></div>
    </div>
    <div class="capsul">
        <label for="currQues">Current question:</label>
        <div class="testInfo" id="currQues"></div>
    </div>
</aside>
<aside id="submitBar">
    <div id="submitBSection">
        <button id='submitB' class='button' onclick='askToEnd()'>Submit</button>
    </div>
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
    startTimer();
    buildList();
    buildQuestion();
</script>