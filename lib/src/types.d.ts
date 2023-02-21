declare namespace App {
    // @ts-ignore
    declare namespace Route {
        export type NamePath = "login" | "logout" | "front.stories.show";
        export type Name = "login" | "logout" | "front.stories.show";
        export type Path = "/login" | "/logout" | "/stories/{story}";
        export type Params = {
            login: never;
            logout: never;
            "front.stories.show": {
                story?: string | number | boolean;
            };
        };

        export type Method = "GET" | "POST" | "PUT" | "PATCH" | "DELETE";
        export interface Entity {
            name: Route.Name;
            path: Route.Path;
            params?: Route.Params[Route.Name];
            method: Route.Method;
        }

        // @ts-ignore
        declare namespace Typed {
            type Login = {
                name: "login";
                params?: undefined;
                query?: Record<string, string | number | boolean>;
                hash?: string;
            };
            type Logout = {
                name: "logout";
                params?: undefined;
                query?: Record<string, string | number | boolean>;
                hash?: string;
            };
            type FrontStoriesShow = {
                name: "front.stories.show";
                params: {
                    story: string | number | boolean;
                };
                query?: Record<string, string | number | boolean>;
                hash?: string;
            };
        }
        export type Type =
            | Route.Typed.Login
            | Route.Typed.Logout
            | Route.Typed.FrontStoriesShow;
        export type TypeGet = Route.Typed.Login | Route.Typed.FrontStoriesShow;
        export type TypePost = Route.Typed.Logout;
        export type TypePut = never;
        export type TypePatch = never;
        export type TypeDelete = never;
    }
}

declare namespace Inertia {
    export interface Page<T> {
        component: string;
        props: T;
        url: string;
        version: string;
        scrollRegions: string[];
        rememberedState: Record<string, unknown>;
        resolvedErrors: Record<string, unknown>;
    }
    export interface PageProps {
        user: any;
        jetstream?: any;
        [x: string]: unknown;
        errors: any;
    }
}
