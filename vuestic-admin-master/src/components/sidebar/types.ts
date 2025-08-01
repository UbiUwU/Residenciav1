export interface INavigationRoute {
  name: string
  displayName: string
  meta: {
    icon: string
    allowedRoles?: number[]
  }
  children?: INavigationRoute[]
}
