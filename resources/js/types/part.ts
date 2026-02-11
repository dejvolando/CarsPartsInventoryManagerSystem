import {Car} from "@/types/car";

export interface Part{
    id: number
    name: string,
    serial_number: string,
    car_id: number,
    car?: Car | null;
}
