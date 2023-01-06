<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity;

\SV\StandardLib\Helper::repo()->aliasClass(
    'TickTackk\UpdateAnyResource\XFRM\Entity\ResourceItem',
    \XF::$versionId < 2020000
        ? 'TickTackk\UpdateAnyResource\XFRM\Entity\XF21\ResourceItem'
        : 'TickTackk\UpdateAnyResource\XFRM\Entity\XF22\ResourceItem'
);