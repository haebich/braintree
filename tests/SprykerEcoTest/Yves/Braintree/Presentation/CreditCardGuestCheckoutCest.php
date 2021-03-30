<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Yves\Braintree\Presentation;

use SprykerEcoTest\Yves\Braintree\BraintreePresentationTester;
use SprykerEcoTest\Yves\Braintree\PageObject\ProductDetailPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Yves
 * @group Braintree
 * @group Presentation
 * @group CreditCardGuestCheckoutCest
 * Add your own group annotations below this line
 */
class CreditCardGuestCheckoutCest
{
    /**
     * @skip
     *
     * @param \SprykerEcoTest\Yves\Braintree\BraintreePresentationTester $i
     *
     * @return void
     */
    public function creditCardCheckoutAsGuest(BraintreePresentationTester $i)
    {
        $i->wantToTest('That i can go through credit card checkout as guest');
        $i->addToCart(ProductDetailPage::URL);
        $i->checkoutWithCreditCardAsGuest();
    }
}
