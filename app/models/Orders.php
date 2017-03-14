<?php

use Phalcon\Mvc\Model\Query\Builder as QueryBuilder;

class Orders extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $product_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $quantity;

    /**
     *
     * @var double
     * @Column(type="double", nullable=true)
     */
    protected $total;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $date;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field product_id
     *
     * @param integer $product_id
     * @return $this
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * Method to set the value of field quantity
     *
     * @param integer $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Method to set the value of field total
     *
     * @param double $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Method to set the value of field date
     *
     * @param string $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field product_id
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Returns the value of field quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Returns the value of field total
     *
     * @return double
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Returns the value of field date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        //$this->setSchema("store");

        $this->belongsTo(
            "user_id",
            "Users",
            "id",
            [
                "reusable" => true,
            ]
        );

        $this->belongsTo(
            "product_id",
            "Products",
            "id",
            [
                "reusable" => true,
            ]
        );
    }

    /**
     * Calculate order total and date
     */
    public function beforeSave()
    {
        $product = Products::findFirstByid($this->product_id);

        // apply promo
        if ($product->getName() === 'Pepsi Cola' && $this->quantity >= 3) {
            $product->setPrice(0.8 * $product->price);
        }

        // set order total
        $this->total = $this->quantity * $product->getPrice();

        // place timestamp
        $this->date = date('Y-m-d h:i:s');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'orders';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders[]|Orders
     */
    public static function find($parameters = null)
    {
        $builder = new QueryBuilder();
        return $builder
            ->addFrom("Orders", "o")
            ->innerJoin("Users", "o.user_id = u.id", "u")
            ->innerJoin("Products", "o.product_id = p.id", "p")
            ->where("o.date > " . $parameters['date'] . " AND (u.name LIKE :terms: OR p.name LIKE :terms:)")
            ->orderBy("o.id ASC")
            ->getQuery()
            ->execute(
                array(
                    'terms' => '%' . $parameters['terms'] . '%')
                );
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
