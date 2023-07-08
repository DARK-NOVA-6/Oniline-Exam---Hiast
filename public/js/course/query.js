function updateRowQ(tx, arx, me) {
    // console.log(tx);
    // console.log(arx);
    arrOfThings = [];
    formFields = {};
    formFields['NAME'] = [];
    formFields['EMAIL'] = [];
    formFields['PHONE'] = [];
    formFields['PASSWORD'] = [];
    formFields['NORMAL'] = [];
    ids = [];
    table = me.tableContent.tableName;
    console.log(table);
    tx.forEach(t => {
        if (t.type === undefined) {
            t.type = 'NORMAL';
        }

        if (t.type === 'EMAIL') {
            t.value = t.value.substr(0, t.value.length - '@hiast.edu.sy'.length);
        }

        arrOfThings.push(
            new TextInput(
                t.name, t.type, t.value,
                t.isEditable, 0,
                (table === 'Users'), false,
                false,
            )
        );

        id = arrOfThings[arrOfThings.length - 1].id;

        if (t.type === 'NAME' || t.type === 'EMAIL' || t.type === 'PHONE') {
            formFields[t.type].push({ id: id, require: true }); //?????
        } else if (t.type === 'PASSWORD') {
            formFields[t.type].push({ passwordId: id });
        } else {
            formFields['NORMAL'].push({ id: id, require: false });
        }
    });

    arx.forEach(rx => {
        arrOfThings.push(
            new Arr(
                rx.name, rx.arrx, 0,
                (table == 'Users'), rx.isYesNo,
                rx.add, rx.delete, rx.edit
            )
        );
    });

    arrOfThings2 = [];
    arrOfThings.forEach(
        e => {
            arrOfThings2.push((e).returnMe());
        }
    );

    insertContent(arrOfThings2);
    me.updateContent = arrOfThings;
    initializeFormValidator('ok', 'fakeOk', formFields);
}