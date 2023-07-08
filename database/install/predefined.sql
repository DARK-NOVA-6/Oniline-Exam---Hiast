insert into pre_def_info_type (info_type)
values ('IMAGE');
insert into pre_def_info_type (info_type)
values ('VIDEO');
insert into pre_def_info_type (info_type)
values ('AUDIO');
insert into pre_def_info_type (info_type)
values ('FILE');
insert into pre_def_info_type (info_type)
values ('NAME');
insert into pre_def_info_type (info_type)
values ('PASSWORD');
insert into pre_def_info_type (info_type)
values ('EMAIL');
insert into pre_def_info_type (info_type)
values ('DATE');
insert into pre_def_info_type (info_type)
values ('PHONE');
insert into pre_def_info_type (info_type)
values ('NORMAL_TEXT');

insert into pre_def_attachment_type (attachment_type)
values ('IMAGE');
insert into pre_def_attachment_type (attachment_type)
values ('VIDEO');
insert into pre_def_attachment_type (attachment_type)
values ('AUDIO');
insert into pre_def_attachment_type (attachment_type)
values ('FILE');

insert into pre_def_permission (permission_label)
values ('permission1');
insert into pre_def_permission (permission_label)
values ('permission2');
insert into pre_def_permission (permission_label)
values ('permission3');
insert into pre_def_permission (permission_label)
values ('permission4');
insert into pre_def_permission (permission_label)
values ('permission5');
insert into pre_def_permission (permission_label)
values ('permission6');
insert into pre_def_permission (permission_label)
values ('permission7');

insert into role (role_title, is_active)
values ('HEADQUARTERS', 'Y');
insert into role (role_title, is_active)
values ('ADMIN', 'Y');
insert into role (role_title, is_active)
values ('ADMIN', 'N');
insert into role (role_title, is_active)
values ('STUDENT', 'Y');
insert into role (role_title, is_active)
values ('STUDENT', 'N');
insert into role (role_title, is_active)
values ('TC_ADMIN', 'Y');
insert into role (role_title, is_active)
values ('TC_ADMIN', 'N');
insert into role (role_title, is_active)
values ('VISITOR', 'Y');


insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission1'),
        (select id from role where role_title = 'HEADQUARTERS'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission2'),
        (select id from role where role_title = 'HEADQUARTERS'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission3'),
        (select id from role where role_title = 'HEADQUARTERS'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission4'),
        (select id from role where role_title = 'HEADQUARTERS'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission5'),
        (select id from role where role_title = 'HEADQUARTERS'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission6'),
        (select id from role where role_title = 'HEADQUARTERS'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission7'),
        (select id from role where role_title = 'HEADQUARTERS'));

insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission1'),
        (select id from role where role_title = 'VISITOR'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission2'),
        (select id from role where role_title = 'VISITOR'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission3'),
        (select id from role where role_title = 'VISITOR'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission4'),
        (select id from role where role_title = 'VISITOR'));

insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission1'),
        (select id from role where role_title = 'ADMIN' and is_active = 'Y'));
insert into role_permission (pre_def_permission_id, role_id)
values ((select id from pre_def_permission where permission_label = 'permission2'),
        (select id from role where role_title = 'ADMIN' and is_active = 'Y'));

insert into additional_info (role_id, pre_def_info_type_id, info_key, required, editable, erasible)
values ((select id from role where role_title = 'ADMIN' and is_active = 'Y'),
        (select id from pre_def_info_type where info_type = 'PHONE'),
        'Phone', 'N', 'Y', 'Y');
insert into additional_info (role_id, pre_def_info_type_id, info_key, required, editable, erasible)
values ((select id from role where role_title = 'ADMIN' and is_active = 'Y'),
        (select id from pre_def_info_type where info_type = 'IMAGE'),
        'Profile Photo', 'N', 'Y', 'Y');
insert into additional_info (role_id, pre_def_info_type_id, info_key, required, editable, erasible)
values ((select id from role where role_title = 'STUDENT' and is_active = 'Y'),
        (select id from pre_def_info_type where info_type = 'IMAGE'),
        'Profile Photo', 'N', 'Y', 'Y');
insert into additional_info (role_id, pre_def_info_type_id, info_key, required, editable, erasible)
values ((select id from role where role_title = 'STUDENT' and is_active = 'Y'),
        (select id from pre_def_info_type where info_type = 'IMAGE'),
        'Personal Photo', 'N', 'Y', 'Y');
insert into additional_info (role_id, pre_def_info_type_id, info_key, required, editable, erasible)
values ((select id from role where role_title = 'STUDENT' and is_active = 'Y'),
        (select id from pre_def_info_type where info_type = 'IMAGE'),
        'Some Info', 'N', 'Y', 'Y');

