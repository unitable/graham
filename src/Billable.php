<?php

namespace Unitable\Graham;

/**
 * @property int $id
 */
trait Billable {

    use Concerns\ManagesSubscriptions;
    use Concerns\ManagesInvoices;

}
