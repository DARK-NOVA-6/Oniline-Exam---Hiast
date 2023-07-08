<div class="container" id='listOfButtons'>
</div>  
<script>
    localURL = '<?php echo URL?>';
    arrayOfButtons = [
        {
            id      :1,
            name    :'Dashboard',
            icon    :'',
            url     :'dashboard/',
            per:'dashboard/',
            action  :url=>goto(url)
        },{
            id      :2,
            name    :'Generate new Test',
            icon    :'',
            url     :'test/add_test',
            per:'test/add_test',
            action  :url=>goto(url)  
        },{
            id      :3,
            name    :'Start new Season',
            icon    :'',
            url     :'courses/start_new_season',
            per:'courses/start_new_season',
            action  :url=>goto(url)  
        },{
            id      :4,
            name    :'Manage additional Information',
            icon    :'',
            url     :'user/add_info',
            per:'user/add_info',
            action  :url=>goto(url)  
        },{
            id      :5,
            name    :'Edit Profile Design',
            icon    :'',
            url     :'user/profile_design',
            per:'user/profile_design',
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