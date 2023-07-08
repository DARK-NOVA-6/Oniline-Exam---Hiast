<?php

class QuestionItem {
    public function __construct(int $id,int $order,String $title, String $text, OptionItem ...$options) {
        $this->id = $id;
        $this->order = $order;
        $this->title = $title;
        $this->text = $text;
        $this->options = $options;
    }
}

