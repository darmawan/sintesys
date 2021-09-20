<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Article_model extends CI_Model {
    private $article = DB_ARTICLE; 
    private $articlecat = DB_CATEGORY;
    public function get_trans($article_id = '', $lang_id = 1) {
        $this->db->from($this->article);

        $this->db->where('article_id', $article_id);
        $this->db->where('lang_id', $lang_id);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
    public function get_article($article_id = '', $lang_id = 1) {
        $this->db->from($this->article);
        
        $this->db->where('article_id', $article_id);
        $this->db->where('lang_id', $lang_id);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
    public function get_listarticle($param,$pot='') {
        $this->db->select('a.name, b.*');
        $this->db->from($this->articlecat .' a');
        $this->db->join($this->article .' b', 'a.article_id=b.article_id', 'left');
        $this->db->order_by('b.publish_date DESC');
        $this->db->where('a.name',$param);
        if($pot!='') { $this->db->where('lower(b.tags)', strtolower($pot)); }
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    public function get_article_category($article_id) {
        $this->db->from($this->articlecat);
        $this->db->where('article_id',$article_id);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
    public function get_article_type($type_id,$grpby='',$limit=6) {
        $this->db->limit($limit);
        $this->db->from($this->article);
        $this->db->where($this->article.'.type_id',$type_id);
        ($grpby<>'') ?  $this->db->group_by($grpby):'';
        ($grpby=='') ? $this->db->join('ad_image','article_id=ad_image.reffid' ,'left'):'';
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : FALSE;                
    }
    public function get_category($catid,$tags='',$grp=0,$orderby='') {
        $this->db->select('a.name, a.cat_id, b.*');
        // $this->db->select('a.name, a.cat_id, b.*, c.image_path');
        $this->db->from($this->articlecat .' a');
        $this->db->join($this->article .' b', 'a.article_id=b.article_id', 'left');
        // $this->db->join('ad_image c','a.article_id=c.reffid' ,'left');
        ($orderby=='') ? $this->db->order_by('b.publish_date DESC'):$this->db->order_by($orderby.' ASC');
        ($grp>0) ? $this->db->group_by('b.tags'):'';
        ($tags<>'') ? $this->db->where('b.tags',$tags):'';
        $this->db->where('a.name',$catid);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    public function get_categorylist($catid) {
        $this->db->select('a.*');
        $this->db->from($this->articlecat .' a');
        $this->db->order_by('a.cat_id');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    public function get_article_list_count($lang_id = 1, $type_id = 21) {
        $query = $this->db->get_where($this->article, array('is_published'=>1,'type_id'=>$type_id));
        return $query->num_rows();
    }
    public function get_article_list($lang_id = 1, $start_idx = 0, $limit = 10, $type_id = 21) {
        $this->db->limit($limit,$start_idx);
        $this->db->from($this->article);
        $this->db->where('is_published', 1);
        $this->db->order_by('publish_date DESC, article_title ASC');
        $this->db->where('type_id', $type_id);
        $query = $this->db->get();
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    public function get_article_listag($tag = '', $start_idx = 0, $limit = 4) {
        $this->db->limit($limit,$start_idx);
        $this->db->from($this->article);
        $this->db->where('is_published', 1);
        $this->db->where('tags', $tag);
        ($tag=='') ? '':$this->db->order_by('publish_date DESC');
        $query = $this->db->get();
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    public function get_article_listbytag_count($cat_id = 1, $tag = '') {
//        $query = $this->db->get_where($this->article, array('is_published'=>1,'tags'=>$tag));
        $this->db->select('a.name, a.cat_id, b.*');
        $this->db->from($this->articlecat .' a');
        $this->db->join($this->article .' b', 'a.article_id=b.article_id', 'left');
        $this->db->where('b.is_published', 1);
        $this->db->order_by('b.publish_date DESC');
        $this->db->where('a.cat_id', $cat_id);
        $this->db->where('b.tags', $tag);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function get_article_listbytag($cat_id = 1, $start_idx = 0, $limit = 10, $tag = '') {
        $this->db->limit($limit,$start_idx);
        $this->db->select('a.name, a.cat_id, b.*');
        $this->db->from($this->articlecat .' a');
        $this->db->join($this->article .' b', 'a.article_id=b.article_id', 'left');
        $this->db->where('b.is_published', 1);
        $this->db->order_by('b.publish_date DESC');
        $this->db->where('a.cat_id', $cat_id);
        $this->db->where('b.tags', $tag);
        $query = $this->db->get();
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    public function get_categorybytag($tag='',$cat='',$grp=0, $limit=0, $join='left') {
        ($limit>0) ? $this->db->limit(1):'';
        $this->db->select('a.name, a.cat_id, b.*');
        $this->db->from($this->articlecat .' a');
        $this->db->join($this->article .' b', 'a.article_id=b.article_id', $join);        
        ($grp>0) ? $this->db->group_by('b.tags'):'';
        ($cat<>'') ? $this->db->where('a.name',$cat):'';
        ($tag<>'') ? $this->db->where('b.tags',$tag):'';
//        $this->db->where('b.is_published', 1);
        $this->db->order_by('b.publish_date DESC');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? (($limit>1) ? $query->row():$query->result()) : FALSE;
    }
    public function get_articleModul($cat='', $tipe='', $limit=0) {
        ($limit>0) ? $this->db->limit($limit):'';
        $this->db->select('a.name, a.cat_id, b.*');
        $this->db->from($this->articlecat .' a');
        $this->db->join($this->article .' b', 'a.article_id=b.article_id', 'left');
        $this->db->order_by('b.article_id ASC');
        ($cat<>'') ? $this->db->where('a.cat_id',$cat):'';
        ($tipe<>'') ? $this->db->where('b.type_id',$tipe):'';
        $this->db->where('b.is_published',1);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    public function get_search($param, $start_idx = 0, $limit = 5) {
        $artikel = TBL_ARTICLE;
        $news = TBL_NEWS;
//        MATCH(ad_article.article_title, ad_article.content) AGAINST ('".$param."')
//        MATCH(ad_news.news_title , ad_news.content) AGAINST ('".$param."')
        $sql = "SELECT tid,title,pdate,content,tipe FROM (
                SELECT $artikel.article_id AS tid, $artikel.article_title AS title, $artikel.publish_date AS pdate, $artikel.content as content, 'artikel' as tipe FROM $artikel WHERE LOWER($artikel.article_title) like '%".strtolower($param)."%' OR LOWER($artikel.content) like '%".strtolower($param)."%' 
                UNION ALL
                SELECT $news.news_id AS tid, $news.news_title AS title, $news.publish_date AS pdate, $news.content as content, 'warta' as tipe FROM $news WHERE LOWER($news.news_title) like '%".strtolower($param)."%' OR LOWER($news.content) like '%".strtolower($param)."%' ) as aep order by pdate desc";
        
        $sql2 = ($start_idx==0) ? $sql . ' limit '.$limit : $sql . ' limit '.$limit.','.$start_idx;
        $query = $this->db->query($sql2);
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
        
    }
    public function get_search_count($param) {
        $artikel = TBL_ARTICLE;
        $news = TBL_NEWS;
        $sql = "SELECT tid,title,pdate,content,tipe FROM (
                SELECT $artikel.article_id AS tid, $artikel.article_title AS title, $artikel.publish_date AS pdate, $artikel.content as content, 'artikel' as tipe FROM $artikel WHERE LOWER($artikel.article_title) like '%".strtolower($param)."%' OR LOWER($artikel.content) like '%".strtolower($param)."%' 
                UNION ALL
                SELECT $news.news_id AS tid, $news.news_title AS title, $news.publish_date AS pdate, $news.content as content, 'warta' as tipe FROM $news WHERE LOWER($news.news_title) like '%".strtolower($param)."%' OR LOWER($news.content) like '%".strtolower($param)."%' ) as aep";
        $query = $this->db->query($sql);
        
        return $query->num_rows();
        
    }
    public function getImgArticle($param) {
        $this->db->limit(1);
        $this->db->from(DB_IMAGE);
        $this->db->where('reffid', $param);
        $this->db->order_by('mainimg', 'DESC');
        $query = $this->db->get();
        if ($query) {
            return ($query->num_rows() > 0) ? $query->row() : FALSE;
        } else {
            return FALSE;
        }
    }
}

?>