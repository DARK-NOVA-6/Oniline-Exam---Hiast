class Tile {
    constructor(
        tileId,
        tileName,
        tileImg,
        isTable = true,
        isFake = false,
        parent = 'drawer'
    ) {
        this.tileId = tileId;
        this.tileName = tileName;
        this.id = tileName.replace(/ +/g, '');
        this.tileImg = tileImg;
        this.isFake = isFake;
        this.parent = parent;
        this.isTable = isTable;
        this.buildTile();
    }
    buildTile() {
        $('<div/>', {
                id: this.id + 'Tile',
                class: 'tile'
            })
            .appendTo('#' + this.parent);

        if (!this.isFake) {
            $('<div/>', {
                class: 'tileIconContainer'
            }).append(
                $('<img>', {
                    src: this.tileImg,
                    class: 'tileIcon'
                })
            ).appendTo('#' + this.id + 'Tile');

            $('<div/>', {
                    class: 'title'
                })
                .text(this.tileName)
                .appendTo('#' + this.id + 'Tile');

            var me = this;
            $('#' + this.id + 'Tile')
                .on('click',
                    () => {
                        var tableName = (me.tileName).toLowerCase();
                        tableName = tableName.replace(/ /g, '_');
                        tableName = tableName.substr(0, tableName.length - 1);
                        if (this.isTable) {
                            window.location.href = LocalURL + 'dashboard/' + tableName;
                        } else {
                            window.location.href = LocalURL + 'home/';
                        }
                    }
                );
        }
    }
}


class Text {
    constructor(text, parent = 'drawer') {
        this.text = text;
        this.someLetters = this.text.substr(0, 4);
        this.someLetters = this.someLetters.toUpperCase();
        this.id = text.replace(/:/g, '');
        this.id = this.id.replace(/ /g, '');
        $('#' + parent)
            .append(
                $('<div/>', {
                    id: this.id,
                    class: 'drawerLabel'
                })
                .text(this.someLetters),

            );
    }

    hover() {
        $('#' + this.id)
            .text(this.text);
    }

    unhover() {
        $('#' + this.id)
            .text(this.someLetters);
    }
}

class Line {
    constructor(width, parent = 'drawer') {
        this.width = width;
        this.color = (width == 1) ? 'white' : '#090144';
        var spacer = null;
        if (width != 1) {
            spacer = $('<br/>');
        }
        $('#' + parent)
            .append(
                $('<div/>', {
                    class: 'fancyHr'
                })
                .css({
                    'border-style': 'solid',
                    'border-width': width,
                    'background-color': this.color,
                    'border-color': this.color,
                }),
                spacer
            );
    }
}