<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Script;

use Raptorio\Blockchain\Collection\StaticBufferCollection;
use Raptorio\Blockchain\Serializer\Script\ScriptWitnessSerializer;
use BitWasp\Buffertools\BufferInterface;

class ScriptWitness extends StaticBufferCollection implements ScriptWitnessInterface
{
    /**
     * @param ScriptWitnessInterface $witness
     * @return bool
     */
    public function equals(ScriptWitnessInterface $witness): bool
    {
        $nStack = count($this);
        if ($nStack !== count($witness)) {
            return false;
        }

        for ($i = 0; $i < $nStack; $i++) {
            if (false === $this->offsetGet($i)->equals($witness->offsetGet($i))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return \BitWasp\Buffertools\BufferInterface
     */
    public function getBuffer(): BufferInterface
    {
        return (new ScriptWitnessSerializer())->serialize($this);
    }
}
