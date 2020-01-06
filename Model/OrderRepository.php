<?php

namespace MageSuite\OrderExport\Model;

class OrderRepository
{
    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $criteriaBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    public function __construct(
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $criteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder
    ) {
        $this->orderRepository = $orderRepository;
        $this->criteriaBuilder = $criteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    public function getOrdersList($filtersData)
    {
        foreach ($filtersData as $filterData) {
            if (empty($filterData['field']) || empty($filterData['value'])) {
                continue;
            }

            $filter = $this->filterBuilder
                ->setField($filterData['field'])
                ->setValue($filterData['value'])
                ->setConditionType($filterData['condition'])
                ->create();

            $this->criteriaBuilder->addFilters([$filter]);
        }

        $searchCriteria = $this->criteriaBuilder->create();

        return $this->orderRepository->getList($searchCriteria)->getItems();
    }

    public function getById($orderId)
    {
        try {
            return $this->orderRepository->get($orderId);
        } catch (\Magento\Framework\Exception\InputException $e) {
            return null;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }
}
