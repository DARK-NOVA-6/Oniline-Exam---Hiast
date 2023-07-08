class Button {
    constructor(id, name, icon, url, action) {
        this.id = id;
        this.name = name;
        this.icon = icon;
        this.action = action;
        this.url = url;
        var button =
            $('<div/>', {
                id: this.id + 'button',
                class: 'button homeButton'
            })
            .append(
                $('<div/>').text(this.name),

                $('<img/>', {
                    src: this.icon
                })
            )
            .on('click', e => this.action(url));

        this.button = button;
    }
    returnMe() {
        return this.button;
    }
}


function addArrtoDiv(arrs, divId) {
    arrs.forEach(
        arr => {
            if (hasPre(arr.per)) {
                $('#' + divId)
                    .append(
                        (
                            new Button(
                                arr.id, arr.name, arr.icon, arr.url, arr.action
                            )
                        ).returnMe()
                    );
            }
        }
    );
}

function goto(url) {
    window.location.href = localURL + url;
}

function hasPre(permission) {
    return permission;
}