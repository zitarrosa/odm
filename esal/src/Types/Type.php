<?php
namespace Zitarrosa\ESAL;

/**
 * ElasticSearch Type
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
abstract class Type
{
    const ATTACHMENT = 'attachment';
    const TARRAY = 'array';
    const BINARY = 'binary';
    const BOOLEAN = 'boolean';
    const BYTE = 'byte';
    const COMPLETION = 'completion';
    const DATE = 'date';
    const FLOAT = 'float';
    const GEO_POINT = 'geo_point';
    const GEO_SHAPE = 'geo_shape';
    const INTEGER = 'integer';
    const IP = 'ip';
    const LONG = 'long';
    const MURMUR3 = 'murmur3';
    const NESTED = 'nested';
    const OBJECT = 'object';
    const SHORT = 'short';
    const STRING = 'string';
    const TOKEN_COUNT = 'token_count';
}