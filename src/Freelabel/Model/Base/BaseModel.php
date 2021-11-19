<?php

namespace Freelabel\Model\Base;


class BaseModel
{
    /**
     * @param mixed $object
     *
     * @return $this
     */
    public function loadFromArray($object)
    {
        if ($object) {
            foreach ($object as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
        return $this;
    }
}
