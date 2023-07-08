
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
curNumber = parseInt(Math.ceil(from/curTotRow));
</script>

<div class="hideScroll" id="drawer">
    
</div>
<div class="containerWithDrawer">
    <div id="container">

    </div>
</div>
<script src="<?php echo URL;?>public/js/admin.js"></script>

<script>
rolesImg ="<?php echo URL;?>public/icon/roles.svg";
usersImg ="<?php echo URL;?>public/icon/users.svg";
coursesImg ="<?php echo URL;?>public/icon/courses.svg";
topicsImg ="<?php echo URL;?>public/icon/topics.svg";
questionsImg ="<?php echo URL;?>public/icon/questions.svg";
texts = [];
new Tile(1,'','',false,true);
new Tile(2,'','',false,true);
new Tile(3,'','',false,true);
texts.push(new Text('Dashboard:'));
new Line(1);
new Tile(4,'Roles',rolesImg);
new Tile(5,'Users',usersImg);
new Tile(6,'Courses',coursesImg);
new Tile(7,'Topics',topicsImg);
new Tile(8,'Questions',questionsImg);
new Tile(9,'Test Centers',questionsImg);
new Line(2);
texts.push(new Text('Home'));
new Line(1);
new Tile(10,'Home',questionsImg,false);
new Tile(11,'','',false,true);
new Tile(12,'','',false,true);

scrollDrawerUp();

newTable = new Table(1, tableContent, curTotRow);
// newTable.loading();
</script>
    