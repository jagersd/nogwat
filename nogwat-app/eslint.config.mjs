import js from '@eslint/js'
import globals from 'globals'
import pluginVue from 'eslint-plugin-vue'

export default [
  {
    ignores: ['android/', 'dist/', 'node_modules/'],
  },
  js.configs.recommended,
  ...pluginVue.configs['flat/essential'],
  {
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
      },
      ecmaVersion: 2022,
      sourceType: 'module',
    },
    rules: {
      'no-console': 'off',
      'no-debugger': 'warn',
      'vue/multi-word-component-names': 'off',
      // Ionic's slot API intentionally uses these template attributes.
      'vue/no-deprecated-slot-attribute': 'off',
      // The legacy Options API component registration is runtime-safe; clean it
      // up incrementally rather than blocking the platform upgrade.
      'vue/no-unused-components': 'off',
    },
  },
]
