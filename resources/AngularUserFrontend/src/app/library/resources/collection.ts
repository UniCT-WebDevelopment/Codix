import { Resource } from './resource';

export class Collection {
  id: number;
  title: string;
  coverUrl: string;
  url: string;
  type: string = 'collection';
}
