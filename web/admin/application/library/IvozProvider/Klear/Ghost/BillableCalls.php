<?php

use Ivoz\Kam\Domain\Model\TrunksCdr\TrunksCdrDto;
use Ivoz\Cgr\Domain\Model\TpCdr\TpCdr;
use Ivoz\Cgr\Domain\Model\TpDestination\TpDestination;
use Ivoz\Cgr\Domain\Model\TpDestinationRate\TpDestinationRate;
use Ivoz\Cgr\Domain\Model\TpRatingPlan\TpRatingPlan;
use Ivoz\Cgr\Domain\Model\RatingPlan\RatingPlan;

class IvozProvider_Klear_Ghost_BillableCalls extends KlearMatrix_Model_Field_Ghost_Abstract
{
    public function getDestination(TrunksCdrDto $model)
    {
        $dataGateway = \Zend_Registry::get('data_gateway');
        $cgrid = $model->getCgrid();

        /** @var \Ivoz\Cgr\Domain\Model\TpCdr\TpCdrDto $tpCdr */
        $tpCdr = $dataGateway->findOneBy(
            TpCdr::class,
            ["TpCdr.cgrid = '" . $cgrid . "' AND TpCdr.runId = '*default'"]
        );

        if (!$tpCdr) {
            return '';
        }
        $costDetails = $tpCdr->getCostDetails();

        if (!isset($costDetails['Timespans'])) {
            return '';
        }
        $timespan = $costDetails['Timespans'][0];
        $matchedDestId = $timespan['MatchedDestId'];

        /** @var \Ivoz\Cgr\Domain\Model\TpDestination\TpDestinationDto $tpDestination */
        $tpDestination = $dataGateway->findOneBy(
            TpDestination::class,
            ["TpDestination.tag = '" . $matchedDestId . "'"]
        );
        if (!$tpDestination) {
            return '';
        }

        /** @var \Ivoz\Cgr\Domain\Model\TpDestinationRate\TpDestinationRateDto $tpDestinationRate */
        $tpDestinationRate = $dataGateway->find(
            TpDestinationRate::class,
            $tpDestination->getTpDestinationRateId()
        );
        if (!$tpDestinationRate) {
            return '';
        }

        $prefix = $tpDestinationRate->getDestinationPrefix();
        $prefixName = $tpDestinationRate->getDestinationPrefixName();

        return "$prefixName ($prefix)";
    }

    public function getRatingPlan(TrunksCdrDto $model)
    {
        $dataGateway = \Zend_Registry::get('data_gateway');
        $cgrid = $model->getCgrid();

        /** @var \Ivoz\Cgr\Domain\Model\TpCdr\TpCdrDto $tpCdr */
        $tpCdr = $dataGateway->findOneBy(
            TpCdr::class,
            ["TpCdr.cgrid = '" . $cgrid . "' AND TpCdr.runId = '*default'"]
        );

        if (!$tpCdr) {
            return '';
        }
        $costDetails = $tpCdr->getCostDetails();

        if (!isset($costDetails['Timespans'])) {
            return '';
        }
        $timespan = $costDetails['Timespans'][0];
        $ratingPlanId = $timespan['RatingPlanId'];

        /** @var \Ivoz\Cgr\Domain\Model\TpRatingPlan\TpRatingPlanDto $tpRatingPlan */
        $tpRatingPlan = $dataGateway->findOneBy(
            TpRatingPlan::class,
            ["TpRatingPlan.tag = '" . $ratingPlanId . "'"]
        );
        if (!$tpRatingPlan) {
            return '';
        }

        $ratingPlan = $dataGateway->find(
            RatingPlan::class,
            $tpRatingPlan->getRatingPlanId()
        );

        $lang = ucfirst(Zend_Registry::get('currentSystemLanguage')->getLanguage());
        $nameGetter = "getName${lang}";

        return $ratingPlan->{$nameGetter}();
    }
}
