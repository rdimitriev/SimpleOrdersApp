<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Regex;

class SearchForm extends Form
{
    /**
     * Initialize the search form
     */
    public function initialize($entity = null, $options = [])
    {
        $type = new Select(
            "searchType",
            [
                "A" => "All time",
                "L" => "Last 7 days",
                "T" => "Today"
            ]
        );
        $type->setLabel("Type");
        $type->addValidators(
            [
                new PresenceOf(
                    [
                        "message" => "Search Type is required",
                    ]
                )
            ]
        );
        $this->add($type);

        $terms = new Text(
            "terms",
            [
                "placeholder" => "enter search term..."
            ]);
        $terms->setLabel("Terms");
        $terms->addValidators(
            [
                new StringLength(
                    [
                        'max' => 50,
                        'min' => 0,
                        'messageMaximum' => 'Search term is too long',
                        'messageMinimum' => '',
                        'cancelOnFail' => true
                    ]
                ),
                new Regex(
                    [
                        'pattern' => '/^[a-z0-9 .\-]*$/i',
                        'message' => 'Search term must contain only alphanumeric characters'
                    ]
                )
            ]
        );
        $this->add($terms);
    }
}