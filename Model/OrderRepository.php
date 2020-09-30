<?php

namespace MageSuite\OrderExport\Model;

class OrderRepository implements \MageSuite\OrderExport\Model\OrderRepositoryInterface
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
        if (empty($filtersData)){
            throw new \InvalidArgumentException('Missing order filter passed to exporter. Processing stopped to avoid incorrect exporting of all orders from the database.');
        }

        foreach ($filtersData as $filterData) {
            if (empty($filterData['field']) || !isset($filterData['value'])) {
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

        return $this->orderRepository->getList($searchCriteria);
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
