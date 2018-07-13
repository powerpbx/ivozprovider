<?php

namespace Ivoz\Provider\Domain\Model\CallCsv;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Collections\Selectable;

interface CallCsvRepository extends  ObjectRepository, Selectable
{

}


