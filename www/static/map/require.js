var requireIncludes = [];

function define(name, entity)
{
    requireIncludes[name] = entity;
}

function require(name)
{
    return requireIncludes[name];
}

function requires(names)
{
    var items = {};
    names.forEach(function (name) {
        items[name] = requireIncludes[name];
    });
    return items;
}
