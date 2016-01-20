<?php

namespace MultiRouting\Helpers;

\Registry::set('system.mock', \Mockery::mock('\FakeSystemClass')->makePartial());

function file_exists($file)
{
    return \Registry::get('system.mock')->file_exists($file);
}

