import { Resource } from './resource';

export class Series {
  id: number;
  title: string;
  coverUrl: string;
  url: string;
  type: string = 'series';
}
