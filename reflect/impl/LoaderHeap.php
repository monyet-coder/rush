<?php
namespace rush\reflect\impl;

class LoaderHeap extends \SplMaxHeap {
    protected function compare(LoaderImpl $l1, LoaderImpl $l2) {
        return $l1->level - $l2->level;
    }
}