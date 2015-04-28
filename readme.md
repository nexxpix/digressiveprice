# DigressivePrice

Easily create and manage range of quantities with associated prices.


## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is DigressivePrice.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/digressiveprice-module:~1.0
```

## Usage

Once activated, go into the Modules tab of the product you want to add a digressive price to.
You can create a new range, edit or remove an existing one.
Fill the form with following information :
- Quantity
    - from : the quantity which begins your range
    - to : the quantity that ends you range.
- Price
    - default : the tax free price of the product when the quantity is in the defined range
    - promo : the tax free promo price of the product
Then click the "Add" or "Update" button.

Take care of the following :
- "quantity to" has to be greater than or equal to "quantity from"
- a quantity ('from' and/or 'to') can't be included into another range
- a range can't surround another one
- a quantity can't be negative
-

Once created, you have to integrate it to you front office thanks to the loop described later on.

The price will be automatically updated according to the product's quantity in the user's cart.


## Hook

This module is only hooked into the Modules tab of the products.
The Hook used is called "product.tab-content".


## Loop

[digressive]

### Input arguments

|Argument |Description |
|---      |--- |
|**product_id** | The ID of the product to get digressive prices. Example: "product_id=3" |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID                | The digressive price range's ID |
|$PRODUCT_ID        | The product which th current digressive price is linked to |
|$QUANTITY_FROM     | The quantity beginning of the range of the digressive price |
|$QUANTITY_TO       | The quantity ending of the range of the digressive price |
|$PRICE             | The tax free price of the product if the quantity is in the range |
|$PROMO_PRICE       | The promo tax free price of the product if the quantity is in the range |
|$TAXED_PRICE       | The taxed price of the product. Uses the tax rules of the user's country |
|$TAXED_PROMO_PRICE | The taxed promo price of the product. Uses the tax rules of the user's country |

### Example

This example displays the product prices according to all the quantity's ranges

<table>
    <tr>
        <th>Quantity</th>
        <th>Unit Price (with taxes)</th>
    </tr>

    <tr>
        <td>1</td>
        <td>
            {if $IS_PROMO==1}
                {$BEST_TAXED_PRICE} {currency attr="symbol"} <del>{$TAXED_PRICE} {currency attr="symbol"}</del>
            {else}
                {$TAXED_PRICE} {currency attr="symbol"}
            {/if}
        </td>
    </tr>

    {loop type="product" name="theProduct" id={product attr="id"}}
        {if $IS_PROMO==1}
            {loop type="digressive" name="digressivePrice" product_id=$ID}
                <tr>
                    {if $QUANTITY_FROM != 0 && $QUANTITY_TO == 99999}
                        <td>From {$QUANTITY_FROM}</td>
                    {else}
                        <td>From {$QUANTITY_FROM} to {$QUANTITY_TO}</td>
                    {/if}
                    <td>{$TAXED_PROMO_PRICE} {currency attr="symbol"}</td>
                </tr>
            {/loop}
        {else}
            {loop type="digressive" name="digressivePrice" product_id=$ID}
                <tr>
                    {if $QUANTITY_FROM != 0 && $QUANTITY_TO == 99999}
                        <td>From {$QUANTITY_FROM}</td>
                    {else}
                        <td>From {$QUANTITY_FROM} to {$QUANTITY_TO}</td>
                    {/if}
                    <td>{$TAXED_PRICE} {currency attr="symbol"}</td>
                <tr>
            {/loop}
        {/if}
    {/loop}
</table>

## Other ?

As you can see in the example, you can use a high value to manage the last range.
Feel free to use some JavaScript to refresh new price information while changing the selected quantity.
