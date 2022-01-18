<?php


namespace Jazor\AliPay\Models;


class GoodsDetail implements \JsonSerializable
{
    private string $goods_id = '';
    private string $alipay_goods_id = '';
    private string $goods_name = '';
    private int $quantity = 1;
    private float $price = 0.00;
    private string $goods_category = '';
    private string $categories_tree = '';
    private string $show_url = '';

    public function __construct($goods_id = '', $goods_name = '', $quantity = 1, $price = 0.00)
    {
        $this->goods_id = $goods_id;
        $this->goods_name = $goods_name;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'goods_id' => $this->goods_id,
            'alipay_goods_id' => $this->alipay_goods_id,
            'goods_name' => $this->goods_name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'goods_category' => $this->goods_category,
            'categories_tree' => $this->categories_tree,
            'show_url' => $this->show_url,
        ], function ($value){return $value !== '';});
    }

    /**
     * @return string
     */
    public function getGoodsId(): string
    {
        return $this->goods_id;
    }

    /**
     * @param string $goods_id
     */
    public function setGoodsId(string $goods_id): void
    {
        $this->goods_id = $goods_id;
    }

    /**
     * @return string
     */
    public function getAlipayGoodsId(): string
    {
        return $this->alipay_goods_id;
    }

    /**
     * @param string $alipay_goods_id
     */
    public function setAlipayGoodsId(string $alipay_goods_id): void
    {
        $this->alipay_goods_id = $alipay_goods_id;
    }

    /**
     * @return string
     */
    public function getGoodsName(): string
    {
        return $this->goods_name;
    }

    /**
     * @param string $goods_name
     */
    public function setGoodsName(string $goods_name): void
    {
        $this->goods_name = $goods_name;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getGoodsCategory(): string
    {
        return $this->goods_category;
    }

    /**
     * @param string $goods_category
     */
    public function setGoodsCategory(string $goods_category): void
    {
        $this->goods_category = $goods_category;
    }

    /**
     * @return string
     */
    public function getCategoriesTree(): string
    {
        return $this->categories_tree;
    }

    /**
     * @param string $categories_tree
     */
    public function setCategoriesTree(string $categories_tree): void
    {
        $this->categories_tree = $categories_tree;
    }

    /**
     * @return string
     */
    public function getShowUrl(): string
    {
        return $this->show_url;
    }

    /**
     * @param string $show_url
     */
    public function setShowUrl(string $show_url): void
    {
        $this->show_url = $show_url;
    }
}
