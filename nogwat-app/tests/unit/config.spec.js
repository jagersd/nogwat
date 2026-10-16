import { loadEnv } from 'vite'

describe('production configuration', () => {
  it('defines the production API endpoint', () => {
    const env = loadEnv('production', process.cwd(), '')

    expect(env.VITE_ROOT_API).toBe('https://nogwat-app.nl')
  })
})
