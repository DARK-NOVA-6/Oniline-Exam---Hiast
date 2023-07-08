<div class="container" id='listOfButtons'>
</div>  
<script>
    localURL = '<?php echo URL?>';
    arrayOfButtons = [
        {
            id      :1,
            name    :'Roles',
            icon    :'',
            url     :'dashboard/role',
            per     :'dashboard/role',
            action  :url=>goto(url)
        },{
            id      :2,
            name    :'Users',
            icon    :'',
            url     :'dashboard/user',
            per     :'dashboard/user',
            action  :url=>goto(url)  
        },{
            id      :3,
            name    :'Courses',
            icon    :'',
            url     :'dashboard/course',
            per     :'dashboard/course',
            action  :url=>goto(url)  
        },{
            id      :4,
            name    :'Topics',
            icon    :'',
            url     :'dashboard/topic',
            per     :'dashboard/topic',
            action  :url=>goto(url)  
        },{
            id      :5,
            name    :'Questions',
            icon    :'',
            url     :'dashboard/question',
            per     :'dashboard/question',
            action  :url=>goto(url)  
        },{
            id      :6,
            name    :'Test Centers',
            icon    :'',
            url     :'dashboard/test_center',
            per     :'dashboard/test_center',
            action  :url=>goto(url)  
        }
    ];
    setTimeout(
        () =>{
            addArrtoDiv(arrayOfButtons,'listOfButtons');
        },
        100
    );
</script>