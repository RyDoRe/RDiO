module.exports = {
  "env": {
    "browser": true,
    "es6": true
  },
  "globals": {
    "Atomics": "readonly",
    "SharedArrayBuffer": "readonly"
  },
  "parserOptions": {
    "ecmaVersion": 2018,
    "sourceType": "module"
  },
  "extends": [
    "standard"
  ],
  "plugins": [
    'svelte3'
  ],
  "overrides": [
    {
      "files": ['**/*.svelte'],
      "processor": 'svelte3/svelte3',
      "rules": {
        "import/first": "off",
        "import/no-duplicates": "off",
        "import/no-mutable-exports": "off",
        "import/no-unresolved": "off",
        "no-multiple-empty-lines": ["error", { max: 1, maxBOF: 2, maxEOF: 0 }]
      }
    }
  ],
  "rules": {
  }
};
