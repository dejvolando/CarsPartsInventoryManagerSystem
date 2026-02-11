import {Part} from "@/types/part";

export interface Car {
    id: number
    name: string,
    registration_number: string,
    is_registered: boolean
    parts?: Part[] | null
}
