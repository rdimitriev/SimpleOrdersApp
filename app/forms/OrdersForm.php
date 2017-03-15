<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Between;

class OrdersForm extends Form
{
    /**
     * Initialize the orders form
     */
    public function initialize($entity = null, $options = [])
    {
        if (!isset($options["edit"])) {
            $element = new Text("id");

            $element->setLabel("Id");

            $this->add(
                $element
            );
        } else {
            $this->add(
                new Hidden("id")
            );
        }

        $user = new Select(
            "user_id",
            Users::find(),
            [
                "using"      => [
                    "id",
                    "name",
                ],
                "useEmpty"   => true,
                "emptyText"  => "...",
                "emptyValue" => "",
            ]
        );
        $user->setLabel("User");
        $user->addValidators(
            [
                new PresenceOf(
                    [
                        "message" => "User is required",
                    ]
                )
            ]
        );
        $this->add($user);

        $product = new Select(
            "product_id",
            Products::find(),
            [
                "using"      => [
                    "id",
                    "name",
                ],
                "useEmpty"   => true,
                "emptyText"  => "...",
                "emptyValue" => "",
            ]
        );
        $product->setLabel("Product");
        $product->addValidators(
            [
                new PresenceOf(
                    [
                        "message" => "Product is required",
                    ]
                )
            ]
        );
        $this->add($product);

        $quantity = new Text("quantity");
        $quantity->setLabel("Quantity");
        $quantity->addValidators(
            [
                new PresenceOf(
                    [
                        "message" => "Quantity is required",
                        "cancelOnFail" => true
                    ]
                ),
                new Digit(
                    [
                        "message" => "Quantity should be a number",
                        "cancelOnFail" => true
                    ]
                ),
                new Between(
                    [
                        "minimum" => 1,
                        "maximum" => 1000000,
                        "message" => "Quantity is out of allowed range",
                    ]
                ),
            ]
        );
        $this->add($quantity);
    }
}