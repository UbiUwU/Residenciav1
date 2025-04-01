/// <reference types="vite/client" />

interface ImportMetaEnv {
  readonly VITE_API_URL: string
  readonly VITE_APP_GTM_ENABLED: string
  readonly VITE_APP_GTM_KEY: string
}

interface ImportMeta {
  readonly env: ImportMetaEnv
}
