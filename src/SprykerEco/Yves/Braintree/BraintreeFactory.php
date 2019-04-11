<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Braintree;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Braintree\Dependency\Client\BraintreeToGlossaryClientInterface;
use SprykerEco\Yves\Braintree\Dependency\Client\BraintreeToPaymentClientInterface;
use SprykerEco\Yves\Braintree\Dependency\Client\BraintreeToQuoteClientInterface;
use SprykerEco\Yves\Braintree\Dependency\Client\BraintreeToShipmentClientInterface;
use SprykerEco\Yves\Braintree\Dependency\Service\BraintreeToUtilEncodingServiceInterface;
use SprykerEco\Yves\Braintree\Form\CreditCardSubForm;
use SprykerEco\Yves\Braintree\Form\DataProvider\BraintreePaypalExpressShipmentFormDataProvider;
use SprykerEco\Yves\Braintree\Form\DataProvider\BraintreePaypalExpressShipmentFormDataProviderInterface;
use SprykerEco\Yves\Braintree\Form\DataProvider\CheckoutShipmentFormDataProvider;
use SprykerEco\Yves\Braintree\Form\DataProvider\CreditCardDataProvider;
use SprykerEco\Yves\Braintree\Form\DataProvider\PayPalDataProvider;
use SprykerEco\Yves\Braintree\Form\DataProvider\SummaryFormDataProvider;
use SprykerEco\Yves\Braintree\Form\PayPalExpressSubForm;
use SprykerEco\Yves\Braintree\Form\PayPalSubForm;
use SprykerEco\Yves\Braintree\Handler\BraintreeHandler;
use SprykerEco\Yves\Braintree\Model\Mapper\PaypalResponse\PaypalResponseMapper;
use SprykerEco\Yves\Braintree\Model\Mapper\PaypalResponse\PaypalResponseMapperInterface;
use SprykerEco\Yves\Braintree\Model\Processor\PaypalResponseProcessor;
use SprykerEco\Yves\Braintree\Model\Processor\PaypalResponseProcessorInterface;

/**
 * @method \SprykerEco\Yves\Braintree\BraintreeConfig getConfig()
 */
class BraintreeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPayPalForm()
    {
        return new PayPalSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPayPalExpressForm()
    {
        return new PayPalExpressSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCreditCardForm()
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPayPalFormDataProvider()
    {
        return new PayPalDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPayPalExpressFormDataProvider()
    {
        return new PayPalDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCreditCardFormDataProvider()
    {
        return new CreditCardDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Braintree\Handler\BraintreeHandlerInterface
     */
    public function createBraintreeHandler()
    {
        return new BraintreeHandler($this->getCurrencyPlugin());
    }

    /**
     * @return \Spryker\Yves\Currency\Plugin\CurrencyPluginInterface
     */
    public function getCurrencyPlugin()
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::PLUGIN_CURRENCY);
    }

    /**
     * @return \SprykerEco\Yves\Braintree\Model\Processor\PaypalResponseProcessorInterface
     */
    public function createResponseProcessor(): PaypalResponseProcessorInterface
    {
        return new PaypalResponseProcessor(
            $this->createPaypalResponseMapper(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Braintree\Model\Mapper\PaypalResponse\PaypalResponseMapperInterface
     */
    public function createPaypalResponseMapper(): PaypalResponseMapperInterface
    {
        return new PaypalResponseMapper($this->getPaymentClient());
    }

    /**
     * @return BraintreeToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): BraintreeToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return BraintreeToQuoteClientInterface
     */
    public function getQuoteClient(): BraintreeToQuoteClientInterface
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return BraintreeToShipmentClientInterface
     */
    public function getShipmentClient(): BraintreeToShipmentClientInterface
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return BraintreeToGlossaryClientInterface
     */
    public function getGlossaryClient(): BraintreeToGlossaryClientInterface
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::CLIENT_GLOSSARY);
    }

    /**
     * @return BraintreeToPaymentClientInterface
     */
    public function getPaymentClient(): BraintreeToPaymentClientInterface
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::CLIENT_PAYMENT);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin()
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::STORE);
    }

    /**
     * @return StepEngineFormDataProviderInterface
     */
    public function createBraintreePaypalExpressShipmentFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CheckoutShipmentFormDataProvider(
            $this->getShipmentClient(),
            $this->getGlossaryClient(),
            $this->getStore(),
            $this->getMoneyPlugin()
        );
    }
}
