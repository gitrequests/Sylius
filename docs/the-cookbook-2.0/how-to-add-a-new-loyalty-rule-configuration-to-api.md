---
layout:
  title:
    visible: true
  description:
    visible: false
  tableOfContents:
    visible: true
  outline:
    visible: true
  pagination:
    visible: true
---

# How to add a new Loyalty Rule Configuration to API?

After creating a new loyalty rule configuration you need to add a new `LoyaltyRuleActionDataTransformer` that implements `Sylius\Plus\Loyalty\Infrastructure\DataTransformer\LoyaltyRuleActionDataTransformerInterface`.&#x20;

To be more flexible, new `LoyaltyRuleConfiguration` is created by a new DTO object: `Sylius\Plus\Loyalty\Application\DTO\LoyaltyRuleAction` that has `configuration` field as an `array` .&#x20;

That field allows you to add any configuration and in the aftermath, you have to transform this object to `Sylius\Plus\Loyalty\Domain\Model\LoyaltyRuleConfiguration\LoyaltyRuleConfigurationInterface` instance by your custom `LoyaltyRuleActionDataTransformer` .

Exemplary `LoyaltyRuleActionYourCustomConfigurationDataTransformer` for new `your_custom_configuration` configuration type:

```php
use Sylius\Plus\Loyalty\Application\DTO\LoyaltyRuleActionInterface as LoyaltyRuleActionInterfaceDTO;
use Sylius\Plus\Loyalty\Domain\Model\LoyaltyRuleActionInterface as LoyaltyRuleActionInterfaceModel;

final class LoyaltyRuleActionYourCustomConfigurationDataTransformer implements LoyaltyRuleActionDataTransformerInterface
{
    /** @var LoyaltyRuleActionFactoryInterface */
    private $loyaltyRuleActionFactory;

    public function __construct(LoyaltyRuleActionFactoryInterface $loyaltyRuleActionFactory)
    {
        $this->loyaltyRuleActionFactory = $loyaltyRuleActionFactory;
    }

    public function transform(LoyaltyRuleActionInterfaceDTO $object, string $to, array $context = []): LoyaltyRuleActionInterfaceModel
    {
        //$object is an input LoyaltyRuleActionInterfaceDTO instance that allow you get new changes and create/update a LoyaltyRuleActionInterfaceModel object

        if (isset($context['object_to_populate'])) {
            /** @var LoyaltyRuleActionInterfaceModel $loyaltyRuleAction */
            $loyaltyRuleAction = $context['object_to_populate'];

            //update object while using PUT or PATCH method

            return $loyaltyRuleAction;
        }

        //create new LoyaltyRuleActionInterfaceModel object while using POST method

        return $this
            ->loyaltyRuleActionFactory
            ->createNewWithDataAndConfiguration('your_custom_configuration', $configuration)
        ;
    }

    public function supportsTransformation(string $actionType): bool
    {
        return $actionType === 'your_custom_configuration';
    }
}
```

Next, you must register `LoyaltyRuleActionYourCustomConfigurationDataTransformer` as a service with a tag `sylius_plus.api.loyalty_rule_action_data_transformer` and key that is the same as the given configuration type:

```xml
<service id="...\LoyaltyRuleActionYourCustomConfigurationDataTransformer" public="true">
    <argument type="service" id="Sylius\Plus\Loyalty\Application\Factory\LoyaltyRuleActionFactory" />
    <tag name="sylius_plus.api.loyalty_rule_action_data_transformer" key="your_custom_configuration" />
</service>
```
