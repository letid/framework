<?php
namespace Letid\Http;
use Letid\Id;
abstract class Response extends Id\Application
{
    // NOTE: Please refer to docs/todo.md
     use Id\Template, Id\Database, Id\Verso, Id\Html;
}
