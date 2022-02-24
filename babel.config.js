const path = require('path')

module.exports = function( api ) {
    api.cache( true );

    const presets = [
    ];

    const plugins = [
        ["@babel/plugin-proposal-decorators", { "legacy": true }],
    ];

    return {
        presets,
        plugins,
    };
};