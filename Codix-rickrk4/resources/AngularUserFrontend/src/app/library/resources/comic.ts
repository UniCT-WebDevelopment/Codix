import { Resource } from './resource';

export class Comic{
  id: number;
  title: string;
  coverUrl: string;
  url: string;
  type: string = 'comic';
  description: string;
  authors: string[];
  publisher: string[];
  tags: string[];
}
