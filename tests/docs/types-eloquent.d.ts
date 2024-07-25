declare namespace App.Models {
  export interface Movie {
    id: string
    title?: string
    subtitles: any[]
    homepage?: 'imdb' | 'tmdb'
    budget: 'high' | 'middle' | 'low'
    revenue?: number
    is_multilingual: boolean
    added_at?: string
    fetched_at?: string
    author_id?: number
    created_at?: string
    updated_at?: string
    show_route?: string
    api_route?: string
    similars_count?: number
    recommendations_count?: number
    members_count?: number
    media_count?: number
    recommendations?: App.Models.Movie[]
    members?: App.Models.Member[]
    author?: App.Models.NestedAuthor
  }
}
