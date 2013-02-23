<?php
namespace rush\inject;

/** @Annotation */
final class Intercept {
    /** @var array<rush\inject\Matcher> */
    public $value;
}