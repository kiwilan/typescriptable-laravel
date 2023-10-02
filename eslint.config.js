import antfu from '@antfu/eslint-config'

// https://github.com/antfu/eslint-config
export default antfu({
  markdown: false,
  ignores: [
    './.github/*',
    './node_modules/*',
    './lib/coverage/*',
    './lib/dist/*',
    './lib/node_modules/*',
    './tests/output/*',
    './vendor/*',
    './**/*.d.ts',
  ],
  rules: {
    'no-console': 'warn',
    'no-unused-vars': 'off',
    'n/prefer-global/process': 'off',
    'node/prefer-global/process': 'off',
  },
})
