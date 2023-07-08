
<script>
tableContent = {};
tableContent.tableName = '<?php echo $tableName;?>';
tableContent.colName = [];
<?php foreach ($colName as $value) { ?>
    tableContent.colName.push('<?php echo $value;?>');
<?php } ?>
tableContent.data = [];

<?php foreach ($data as $arr) { ?> 
    arr =[]; 
    <?php foreach($arr as $val) {?>
        arr.push('<?php echo $val?>');
    <?php } ?>
    tableContent.data.push(arr);
<?php } ?>
curTotRow = <?php echo $curTotRow; ?>;
allRow =<?php echo $allRow; ?>;
from = <?php echo $from; ?>;
to = <?php echo $to; ?>;
</script>

<div class="hideScroll" id="drawer">
    
</div>
<div class="containerWithDrawer">
    <div id="container">

    </div>
</div>
<script src="<?php echo URL;?>public/js/admin.js"></script>

<script>
usersImg ="<?php echo URL;?>public/icon/users.svg";
coursesImg ="<?php echo URL;?>public/icon/courses.svg";
topicsImg ="<?php echo URL;?>public/icon/topics.svg";

new Tile(1,'','',false,true);
new Tile(1,'','',false,true);
new Tile(1,'','',false,true);
new Tile(1,'Past Tests',topicsImg);
new Tile(1,'Upcoming Tests',coursesImg);
new Tile(1,'Student',usersImg);
// new Tile(2,'EditProfileDesine',questionsImg,false);
new Tile(1,'','',false,true);
new Tile(1,'','',false,true);

scrollDrawerUp();

newTable = new Table(1, tableContent, curTotRow);

// newTable.loading();
</script>
    