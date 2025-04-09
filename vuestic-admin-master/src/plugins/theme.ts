import { useColors } from 'vuestic-ui'

export function restoreTheme() {
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme) {
    const { applyPreset } = useColors()
    applyPreset(savedTheme)
  }
}
